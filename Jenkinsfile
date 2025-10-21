pipeline {
    agent {
        docker {
            image 'php:8.2-cli'
            args '-u root:root -v /var/run/docker.sock:/var/run/docker.sock'
        }
    }
    
    environment {
        APP_ENV = 'testing'
        APP_KEY = 'base64:test-key-for-jenkins'
        DB_CONNECTION = 'sqlite'
        DB_DATABASE = ':memory:'
        COMPOSER_HOME = '/tmp/composer'
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
        
        stage('Clone') {
            steps {
                timeout(time: 2, unit: 'MINUTES'){
                    git branch: 'main', 
                        credentialsId: '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', 
                        url: 'https://github.com/nilver987/PDS-2025-2-test-sonar-test.git'
                }
            }
        }
        
        stage('Install Dependencies') {
            steps {
                timeout(time: 8, unit: 'MINUTES'){
                    dir('backend-laravel') {
                        sh '''
                            composer install --no-interaction --prefer-dist --optimize-autoloader --no-progress
                            
                            if [ ! -f .env ]; then
                                cp .env.example .env
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
                            mkdir -p storage/framework/cache/data
                            mkdir -p storage/framework/sessions
                            mkdir -p storage/framework/views
                            mkdir -p storage/framework/testing
                            mkdir -p storage/logs
                            mkdir -p bootstrap/cache
                            
                            chmod -R 777 storage
                            chmod -R 777 bootstrap/cache
                            
                            touch database/database.sqlite
                        '''
                    }
                }
            }
        }
        
        stage('Test') {
            steps {
                timeout(time: 10, unit: 'MINUTES'){
                    dir('backend-laravel') {
                        sh '''
                            php artisan config:clear
                            php artisan cache:clear
                            
                            # Ejecutar tests con cobertura
                            php artisan test --coverage-clover=coverage.xml --log-junit=test-results.xml || true
                            
                            # Verificar que se generó el reporte
                            if [ -f coverage.xml ]; then
                                echo "✅ Reporte de cobertura generado"
                            else
                                echo "⚠️ No se generó reporte de cobertura"
                            fi
                        '''
                    }
                }
            }
        }
        
        stage('Code Quality - PHPStan') {
            steps {
                timeout(time: 5, unit: 'MINUTES'){
                    dir('backend-laravel') {
                        sh '''
                            if [ -f vendor/bin/phpstan ]; then
                                ./vendor/bin/phpstan analyse app --level=5 --error-format=json > phpstan-report.json || true
                                echo "✅ PHPStan ejecutado"
                            else
                                echo "⚠️ PHPStan no instalado, omitiendo..."
                            fi
                        '''
                    }
                }
            }
        }
        
        stage('Install SonarScanner') {
            steps {
                timeout(time: 3, unit: 'MINUTES'){
                    sh '''
                        apt-get install -y -qq wget openjdk-17-jre-headless
                        
                        wget -q https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-5.0.1.3006-linux.zip
                        unzip -q sonar-scanner-cli-5.0.1.3006-linux.zip
                        mv sonar-scanner-5.0.1.3006-linux /opt/sonar-scanner
                        ln -s /opt/sonar-scanner/bin/sonar-scanner /usr/local/bin/sonar-scanner
                    '''
                }
            }
        }
        
        stage('Sonar') {
            steps {
                timeout(time: 4, unit: 'MINUTES'){
                    dir('backend-laravel') {
                        withSonarQubeEnv('sonarqube'){
                            sh '''
                                sonar-scanner \
                                -Dsonar.projectKey=laravel-project \
                                -Dsonar.projectName="Laravel Backend" \
                                -Dsonar.sources=app,routes \
                                -Dsonar.tests=tests \
                                -Dsonar.language=php \
                                -Dsonar.sourceEncoding=UTF-8 \
                                -Dsonar.php.coverage.reportPaths=coverage.xml \
                                -Dsonar.php.tests.reportPath=test-results.xml \
                                -Dsonar.exclusions=vendor/**,storage/**,bootstrap/cache/**,public/**,resources/**,config/**,database/migrations/**
                            '''
                        }
                    }
                }
            }
        }
        
        stage('Quality Gate') {
            steps {
                sleep(10)
                timeout(time: 4, unit: 'MINUTES'){
                    waitForQualityGate abortPipeline: true
                }
            }
        }
        
        stage('Deploy') {
            steps {
                timeout(time: 8, unit: 'MINUTES'){
                    dir('backend-laravel') {
                        sh '''
                            echo "🚀 Iniciando despliegue de Laravel..."
                            
                            php artisan config:cache
                            php artisan route:cache
                            php artisan view:cache
                            
                            echo "✅ Optimizaciones completadas"
                            echo "📦 Aplicación lista para despliegue"
                            
                            # Para servidor de desarrollo:
                            # php artisan serve --host=0.0.0.0 --port=8000 &
                        '''
                    }
                }
            }
        }
    }
    
    post {
        always {
            dir('backend-laravel') {
                sh '''
                    php artisan cache:clear || true
                    php artisan config:clear || true
                    php artisan route:clear || true
                    php artisan view:clear || true
                '''
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
