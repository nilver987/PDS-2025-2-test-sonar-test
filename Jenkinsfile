pipeline {
    agent any

    environment {
        APP_ENV = 'testing'
        APP_KEY = 'base64:your-app-key-here'
        DB_CONNECTION = 'sqlite'
        DB_DATABASE = ':memory:'
    }

    stages {
        stage('Clone') {
            steps {
                timeout(time: 2, unit: 'MINUTES') {
                    git branch: 'main',
                        credentialsId: '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',
                        url: 'https://github.com/nilver987/PDS-2025-2-test-sonar-test.git'
                }
            }
        }

        stage('Install Dependencies') {
            agent {
                docker {
                    image 'php:8.2'
                    args '-v $PWD:/app'
                }
            }
            steps {
                timeout(time: 8, unit: 'MINUTES') {
                    dir('backend-laravel') {
                        sh '''
                            apt-get update && apt-get install -y unzip git zip libzip-dev curl && docker-php-ext-install zip
                            curl -sS https://getcomposer.org/installer | php
                            mv composer.phar /usr/local/bin/composer
                            composer install --no-interaction --prefer-dist --optimize-autoloader
                            cp .env.example .env || true
                            php artisan key:generate || true
                        '''
                    }
                }
            }
        }

        stage('Prepare Environment') {
            agent { docker { image 'php:8.2' } }
            steps {
                timeout(time: 2, unit: 'MINUTES') {
                    dir('backend-laravel') {
                        sh '''
                            mkdir -p storage/framework/{cache,sessions,views}
                            mkdir -p storage/logs
                            chmod -R 775 storage bootstrap/cache
                        '''
                    }
                }
            }
        }

        stage('Test') {
            agent { docker { image 'php:8.2' } }
            steps {
                timeout(time: 10, unit: 'MINUTES') {
                    dir('backend-laravel') {
                        sh 'php artisan test --coverage --coverage-clover=coverage.xml || true'
                    }
                }
            }
        }

        stage('Code Quality - PHPStan') {
            agent { docker { image 'php:8.2' } }
            steps {
                timeout(time: 5, unit: 'MINUTES') {
                    dir('backend-laravel') {
                        sh './vendor/bin/phpstan analyse --error-format=json > phpstan-report.json || true'
                    }
                }
            }
        }

        stage('Sonar') {
            steps {
                timeout(time: 4, unit: 'MINUTES') {
                    dir('backend-laravel') {
                        withSonarQubeEnv('sonarqube') {
                            sh '''
                                sonar-scanner \
                                -Dsonar.projectKey=laravel-project \
                                -Dsonar.sources=app,routes \
                                -Dsonar.tests=tests \
                                -Dsonar.php.coverage.reportPaths=coverage.xml \
                                -Dsonar.php.tests.reportPath=test-results.xml \
                                -Dsonar.exclusions=vendor/**,storage/**,bootstrap/cache/**,public/**,resources/**,config/**,database/**
                            '''
                        }
                    }
                }
            }
        }

        stage('Quality Gate') {
            steps {
                sleep(10)
                timeout(time: 4, unit: 'MINUTES') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }

        stage('Deploy') {
            agent { docker { image 'php:8.2' } }
            steps {
                timeout(time: 8, unit: 'MINUTES') {
                    dir('backend-laravel') {
                        echo "Iniciando despliegue de Laravel..."
                        sh '''
                            php artisan config:cache
                            php artisan route:cache
                            php artisan view:cache
                        '''
                    }
                }
            }
        }
    }

    post {
        always {
            agent { docker { image 'php:8.2' } }
            steps {
                dir('backend-laravel') {
                    sh '''
                        php artisan cache:clear || true
                        php artisan config:clear || true
                        php artisan route:clear || true
                        php artisan view:clear || true
                    '''
                }
            }
        }
        success {
            echo '✅ Pipeline ejecutado exitosamente!'
        }
        failure {
            echo '❌ Pipeline falló. Revisa los logs.'
        }
    }
}
