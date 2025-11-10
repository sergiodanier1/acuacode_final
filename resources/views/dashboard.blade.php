@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Acuapónico - Proyecto SENNOVA</title>
    <link rel="stylesheet" type="text/css" href="css/sergio.css"></head>
<body>
    <div class="panel">
        <!-- Encabezado -->
        <!-- Imagen del sistema acuapónico -->
<div class="image-container mediun">
    <img src="imagenes/acua.jpg" alt="Sistema Acuapónico" class="acuaponic-image">
</div>

        <!-- Qué es un sistema acuapónico -->
        <div class="info-card">
            <h3>¿Qué es un Sistema Acuapónico?</h3>
            <p>Un sistema acuapónico es un ecosistema cerrado donde los desechos producidos por los peces son convertidos por bacterias beneficiosas en nutrientes que las plantas pueden absorber. A su vez, las plantas filtran el agua, que retorna limpia a los tanques de peces. Este ciclo continuo crea un sistema de producción de alimentos altamente eficiente y sostenible.</p>
            
            <div class="system-components">
                <div class="component">
                    <h4>Componente Acuícola</h4>
                    <p>Tanques donde se crían peces u otros organismos acuáticos. Los peces producen desechos ricos en amoníaco que servirán como nutriente para las plantas.</p>
                </div>
                
                <div class="component">
                    <h4>Componente Hidropónico</h4>
                    <p>Zona de cultivo donde las plantas crecen sin suelo, con sus raíces sumergidas en agua enriquecida con nutrientes provenientes de los desechos de los peces.</p>
                </div>
                
                <div class="component">
                    <h4>Componente Microbiológico</h4>
                    <p>Bacterias nitrificantes que convierten el amoníaco tóxico de los desechos de peces en nitritos y luego en nitratos, que son nutrientes esenciales para las plantas.</p>
                </div>
            </div>
        </div>

        <!-- Beneficios de la acuaponía -->
        <div class="info-card">
            <h3>Beneficios de los Sistemas Acuapónicos</h3>
            
            <div class="benefits-grid">
                <div class="benefit">
                    <div class="benefit-icon">💧</div>
                    <h4>Ahorro de Agua</h4>
                    <p>Utiliza hasta 90% menos agua que la agricultura tradicional, ya que el agua se recicla continuamente en el sistema.</p>
                </div>
                
                <div class="benefit">
                    <div class="benefit-icon">🌱</div>
                    <h4>Producción Sostenible</h4>
                    <p>No requiere fertilizantes químicos, ya que los nutrientes provienen de los desechos de los peces.</p>
                </div>
                
                <div class="benefit">
                    <div class="benefit-icon">🚫</div>
                    <h4>Sin Uso de Suelo</h4>
                    <p>Ideal para áreas con suelos pobres o limitado espacio, permitiendo la producción en entornos urbanos.</p>
                </div>
                
                <div class="benefit">
                    <div class="benefit-icon">🔄</div>
                    <h4>Doble Producción</h4>
                    <p>Genera dos productos: proteína animal (peces) y vegetales, maximizando el rendimiento por unidad de área.</p>
                </div>
            </div>
        </div>

        <!-- Proyecto SENNOVA -->
        <div class="info-card">
            <h3>Proyecto SENNOVA: Sistema de Monitoreo Acuapónico</h3>
            <p>En el marco del convenio entre la Universidad Autónoma del Cauca y el SENA, se ha desarrollado un innovador sistema de monitoreo para optimizar la producción en sistemas acuapónicos mediante tecnología de punta.</p>
            
            <div class="project-details">
                <h4>Características del Proyecto</h4>
                <p>Este proyecto implementa un sistema integral de monitoreo que permite el control y seguimiento en tiempo real de los parámetros críticos del sistema acuapónico, garantizando condiciones óptimas para el crecimiento tanto de peces como de plantas.</p>
                
                <div class="project-goals">
                    <h4>Objetivos del Proyecto</h4>
                    
                    <div class="goal">
                        <div class="goal-icon">✓</div>
                        <div>
                            <strong>Automatización del monitoreo:</strong> Implementar sensores para medir parámetros como pH, temperatura, oxígeno disuelto y niveles de nutrientes.
                        </div>
                    </div>
                    
                    <div class="goal">
                        <div class="goal-icon">✓</div>
                        <div>
                            <strong>Optimización de recursos:</strong> Reducir el consumo de agua y energía mediante el control preciso de las condiciones del sistema.
                        </div>
                    </div>
                    
                    <div class="goal">
                        <div class="goal-icon">✓</div>
                        <div>
                            <strong>Incremento de productividad:</strong> Mejorar los rendimientos de producción tanto de peces como de vegetales mediante condiciones controladas.
                        </div>
                    </div>
                    
                    <div class="goal">
                        <div class="goal-icon">✓</div>
                        <div>
                            <strong>Formación especializada:</strong> Capacitar a estudiantes y productores en tecnologías de agricultura sostenible y sistemas de monitoreo.
                        </div>
                    </div>
                </div>
                
                <h4>Tecnologías Implementadas</h4>
                <p>El sistema incorpora sensores IoT, plataformas de visualización de datos, sistemas de alerta automatizados y controles remotos que permiten a los operadores tomar decisiones informadas para mantener el equilibrio del ecosistema acuapónico.</p>
            </div>
        </div>

        <!-- Conclusión -->
        <div class="info-card">
            <h3>Impacto y Futuro</h3>
            <p>El proyecto de sistema acuapónico con monitoreo desarrollado en colaboración con SENNOVA representa un avance significativo en la agricultura sostenible. No solo demuestra la viabilidad de producir alimentos de manera eficiente y respetuosa con el medio ambiente, sino que también sirve como modelo replicable para comunidades rurales y urbanas.</p>
            
            <p>La integración de tecnología de monitoreo en estos sistemas abre nuevas posibilidades para la optimización de recursos y la escalabilidad de la producción, contribuyendo a la seguridad alimentaria y al desarrollo de prácticas agrícolas más sostenibles.</p>
        </div>

        <!-- Footer -->
        <footer>
            <p>Proyecto desarrollado en colaboración con la Universidad Autónoma del Cauca y SENNOVA - SENA</p>
        </footer>
    </div>
</body>
</html>
@endsection