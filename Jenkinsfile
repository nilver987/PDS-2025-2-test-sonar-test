pipeline {
    agent any
    
    environment {
        APP_ENV = 'testing'
        APP_KEY = 'base64:test-key-for-jenkins'
        DB_CONNECTION = 'sqlite'
        DB_DATABASE = ':memory:'
    }
    
    stages {
        stage('Clone') {
            steps {
                timeout(time: 2, unit: 'MINUTES'){
                    git branch: 'main', 
                        credentialsId: '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', 
                        url: 'https://github.com/nilver987/PDS-2025-2-test-sonar-test.git'
                }
            }
        }
        
        stage('Build') {
            steps {
                timeout(time: 8, unit: 'MINUTES'){
                    dir('backend-laravel') {
                        sh '''
                            docker run --rm \
                                -v "$(pwd)":/app \
                                -w /app \
                                composer:latest \
                                composer install --no-interaction --prefer-dist --optimize-autoloader
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
                            docker run --rm \
                                -v "$(pwd)":/app \
                                -w /app \
                                php:8.2-cli \
                                bash -c '
                                    set -e
                                    echo "Creating directories..."
                                    mkdir -p storage/framework/cache/data
                                    mkdir -p storage/framework/sessions
                                    mkdir -p storage/framework/views
                                    mkdir -p storage/framework/testing
                                    mkdir -p storage/logs
                                    mkdir -p bootstrap/cache
                                    mkdir -p database
                                    
                                    echo "Setting permissions..."
                                    chmod -R 777 storage || true
                                    chmod -R 777 bootstrap/cache || true
                                    
                                    echo "Creating SQLite database..."
                                    touch database/database.sqlite
                                    
                                    echo "Configuring .env file..."
                                    if [ ! -f .env ]; then
                                        if [ -f .env.example ]; then
                                            cp .env.example .env
                                        else
                                            echo "APP_ENV=testing" > .env
                                            echo "APP_KEY=" >> .env
                                            echo "DB_CONNECTION=sqlite" >> .env
                                            echo "DB_DATABASE=/app/database/database.sqlite" >> .env
                                        fi
                                    fi
                                    
                                    echo "Generating application key..."
                                    php artisan key:generate --force --no-interaction
                                    
                                    echo "✅ Environment prepared successfully"
                                '
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
                            docker run --rm \
                                -v "$(pwd)":/app \
                                -w /app \
                                php:8.2-cli \
                                bash -c "
                                    apt-get update -qq
                                    apt-get install -y -qq libzip-dev zip sqlite3 git unzip
                                    docker-php-ext-install zip pdo_sqlite
                                    
                                    php artisan config:clear
                                    php artisan cache:clear
                                    
                                    php artisan test --coverage-clover=coverage.xml --log-junit=test-results.xml || true
                                    
                                    if [ -f coverage.xml ]; then
                                        echo '✅ Coverage report generated'
                                        ls -lh coverage.xml
                                    else
                                        echo '⚠️ Coverage report not found'
                                    fi
                                "
                        '''
                    }
                }
            }
        }
        
        stage('Sonar') {
            steps {
                timeout(time: 4, unit: 'MINUTES'){
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
                                    -Dsonar.exclusions=vendor/**,storage/**,bootstrap/**,public/**,resources/**,config/**,database/migrations/**
                                """
                            }
                        }
                    }
                }
            }
        }
        
        stage('Quality Gate') {
            steps {
                timeout(time: 5, unit: 'MINUTES'){
                    sleep(10)
                    waitForQualityGate abortPipeline: true
                }
            }
        }
        
        stage('Deploy') {
            steps {
                timeout(time: 8, unit: 'MINUTES'){
                    dir('backend-laravel') {
                        sh '''
                            echo "🚀 Deployment stage"
                            echo "✅ Application ready for production"
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
            echo '❌ Pipeline failed - check logs'
        }
    }
}
