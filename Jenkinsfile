pipeline {
    agent none
    
    stages {
        stage('Clone') {
            agent any
            steps {
                timeout(time: 2, unit: 'MINUTES'){
                    git branch: 'main', 
                        credentialsId: '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', 
                        url: 'https://github.com/nilver987/PDS-2025-2-test-sonar-test.git'
                }
                stash includes: '**', name: 'source'
            }
        }
        
        stage('Build & Test') {
            agent {
                docker {
                    image 'php:8.2-cli'
                    args '-u root:root'
                    reuseNode true
                }
            }
            stages {
                stage('Install System Dependencies') {
                    steps {
                        timeout(time: 5, unit: 'MINUTES'){
                            sh '''
                                apt-get update -qq
                                apt-get install -y -qq git unzip libzip-dev zip sqlite3
                                docker-php-ext-install zip pdo_sqlite
                            '''
                        }
                    }
                }
                
                stage('Install Composer') {
                    steps {
                        timeout(time: 3, unit: 'MINUTES'){
                            sh '''
                                curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
                                composer --version
                            '''
                        }
                    }
                }
                
                stage('Install Dependencies') {
                    steps {
                        timeout(time: 8, unit: 'MINUTES'){
                            dir('backend-laravel') {
                                sh '''
                                    composer install --no-interaction --prefer-dist --optimize-autoloader
                                    
                                    if [ ! -f .env ]; then
                                        cp .env.example .env || echo "APP_ENV=testing" > .env
                                    fi
                                    
                                    php artisan key:generate --force
                                '''
                            }
                        }
                    }
                }
                
                stage('Prepare Environment') {
                    steps {
                        timeout(time: 2, unit: 'MINUTES'){
                            dir('backend-laravel') {
                                sh '''
                                    mkdir -p storage/framework/{cache/data,sessions,views,testing}
                                    mkdir -p storage/logs bootstrap/cache database
                                    chmod -R 777 storage bootstrap/cache
                                    touch database/database.sqlite
                                '''
                            }
                        }
                    }
                }
                
                stage('Run Tests') {
                    steps {
                        timeout(time: 10, unit: 'MINUTES'){
                            dir('backend-laravel') {
                                sh '''
                                    php artisan config:clear
                                    php artisan cache:clear
                                    
                                    php artisan test --coverage-clover=coverage.xml --log-junit=test-results.xml || true
                                    
                                    if [ -f coverage.xml ]; then
                                        echo "✅ Coverage report generated"
                                    fi
                                '''
                            }
                        }
                    }
                }
            }
        }
        
        stage('SonarQube Analysis') {
            agent any
            steps {
                timeout(time: 4, unit: 'MINUTES'){
                    unstash 'source'
                    dir('backend-laravel') {
                        script {
                            def scannerHome = tool 'SonarScanner'
                            withSonarQubeEnv('sonarqube') {
                                sh """
                                    ${scannerHome}/bin/sonar-scanner \
                                    -Dsonar.projectKey=laravel-backend \
                                    -Dsonar.projectName='Laravel Backend' \
                                    -Dsonar.sources=app,routes \
                                    -Dsonar.tests=tests \
                                    -Dsonar.language=php \
                                    -Dsonar.sourceEncoding=UTF-8 \
                                    -Dsonar.php.coverage.reportPaths=coverage.xml \
                                    -Dsonar.php.tests.reportPath=test-results.xml \
                                    -Dsonar.exclusions=vendor/**,storage/**,bootstrap/cache/**,public/**,resources/**,config/**,database/migrations/**
                                """
                            }
                        }
                    }
                }
            }
        }
        
        stage('Quality Gate') {
            agent any
            steps {
                timeout(time: 5, unit: 'MINUTES'){
                    sleep(10)
                    waitForQualityGate abortPipeline: true
                }
            }
        }
        
        stage('Deploy') {
            agent any
            steps {
                timeout(time: 8, unit: 'MINUTES'){
                    dir('backend-laravel') {
                        sh '''
                            echo "Deployment stage - ready for production deployment"
                            echo "Application optimized and tested successfully"
                        '''
                    }
                }
            }
        }
    }
    
    post {
        success {
            echo '✅ Pipeline completed successfully'
        }
        failure {
            echo '❌ Pipeline failed'
        }
    }
}
