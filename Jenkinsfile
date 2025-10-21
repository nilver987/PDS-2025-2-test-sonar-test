pipeline {
    agent any

    environment {
        // Variables de entorno para Laravel
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
            steps {
                timeout(time: 8, unit: 'MINUTES') {
                    dir('backend-laravel') {
                        // Instalar dependencias de Composer
                        sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
                        
                        // Copiar archivo de entorno
                        sh 'cp .env.example .env'
                        
                        // Generar key de aplicación
                        sh 'php artisan key:generate'
                    }
                }
            }
        }

        stage('Prepare Environment') {
            steps {
                timeout(time: 2, unit: 'MINUTES') {
                    dir('backend-laravel') {
                        // Crear directorios necesarios
                        sh 'mkdir -p storage/framework/cache'
                        sh 'mkdir -p storage/framework/sessions'
                        sh 'mkdir -p storage/framework/views'
                        sh 'mkdir -p storage/logs'
                        
                        // Dar permisos
                        sh 'chmod -R 775 storage'
                        sh 'chmod -R 775 bootstrap/cache'
                    }
                }
            }
        }

        stage('Test') {
            steps {
                timeout(time: 10, unit: 'MINUTES') {
                    dir('backend-laravel') {
                        // Ejecutar tests con PHPUnit y generar reporte de cobertura
                        sh 'php artisan test --coverage --coverage-clover=coverage.xml'
                        
                        // O si usas PHPUnit directamente:
                        // sh './vendor/bin/phpunit --coverage-clover=coverage.xml --log-junit=test-results.xml'
                    }
                }
            }
        }

        stage('Code Quality - PHPStan') {
            steps {
                timeout(time: 5, unit: 'MINUTES') {
                    dir('backend-laravel') {
                        // Análisis estático de código con PHPStan (opcional)
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
                            // Scanner de SonarQube para PHP/Laravel
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
                sleep(10) // segundos
                timeout(time: 4, unit: 'MINUTES') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }

        stage('Deploy') {
            steps {
                timeout(time: 8, unit: 'MINUTES') {
                    dir('backend-laravel') {
                        echo "Iniciando despliegue de Laravel..."
                        
                        // Optimizar aplicación para producción
                        sh 'php artisan config:cache'
                        sh 'php artisan route:cache'
                        sh 'php artisan view:cache'
                        
                        // Ejecutar migraciones (si es necesario)
                        // sh 'php artisan migrate --force'
                        
                        // Iniciar servidor de desarrollo (para testing)
                        echo "php artisan serve --host=0.0.0.0 --port=8000"
                    }
                }
            }
        }
    }

    post {
        always {
            dir('backend-laravel') {
                sh 'php artisan cache:clear || true'
                sh 'php artisan config:clear || true'
                sh 'php artisan route:clear || true'
                sh 'php artisan view:clear || true'
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
