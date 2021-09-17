# Game Winner Calculator

El objetivo de este código es mostrar mi forma de estructurar y ordenar el código a la hora de implementar un trabajo.

En este ejemplo de código he creado un importador de datos CSV sin persistencia.

## ¿Qué hace?

Representa un pequeño programa capaz de identificar el vencedor de unas competiciones de juegos a partir 
de las tablas de resultados de los mismos.

## ¿Cómo lo hace?

Las tablas de resultados de los juegos se presentan dentro de ficheros CSV en un directorio.

A partir de los datos obtenidos recorriendo el CSV, crea unas estructuras de datos simples
que permite un cómodo manejo de los datos y fácil mantenimiento en caso de requerir modificaciones.

He prestado especial atención a que los datos quedasen almacenados en memoria
de una forma coherente, respetando buenas prácticas y que sea flexible frente a cambios. 

Para ello, he creado un  patrón Singleton que asegura que cada importación almacene los datos en una única instancia de torneo.
He llamado torneo al conjunto de juegos que son procesados en una ejecución.

Según se van procesando los ficheros CSV, se van creando los objetos de jugadores y juegos necesarios y se van añadiendo
al Singleton del torneo.

## Estructura del código fuente

Tras la creación de las clases de "modelo" que contienen la información, he pasado a separar la lógica de negocio.

El flujo de ejecucución comienza en el archivo "index.php", desde el que se realiza la llamada a una clase controladora de acchión (que implementa la interfaz IFileAction), recibiendo como parámetro una implementación de parseador de fichero (interfaz IParser) y otra de vista (interfaz IView).
Dotándose a la acción de las capacidades necesarias para cargar el torneo con los datos que se parseen desde fichero y se duelva el resultado desde la vista.

Actualmente es un "parser" de fichero CSV, pero al haberse desacoplado de esta forma, en el futuro se podría cambiar fácilmente por cualquier otra implementación.
Ocurre lo mismo con la vista, que actualmente implementa un resultado muy básico para CLI, pero en el futuro se podría modificar o añadir cualquier otra implementación.
 
## ¿Cómo ejecutar el programa?

Para ejecutar el programa, sólo hay que descargarse el código, que incluye unos archivos CSV de ejemplo y 
lanzar la siguiente línea de comando desde el terminal: 
`php index.php`

## TO-DO: Pendiente de mejora cuando la vida me de ratos

- Introducir tests.

- Añadir Logs en ficheros.

- Mejorar el rendimiento del cálculo de ganadores.

- Crear la interfaz mediante comandos con parámetros, de forma que se indique si sólo se quiere ver los ganadores 
o si también se quieren visualizar los problemas encontrados durante el proceso de parseo.