# Game Winner Calculator

En este ejemplo he intentado crear un importador de datos CSV sin persistencia.

Que a partir de los datos obtenidos recorriendo el CSV, crea unas estructuras de datos simples
que permite un cómodo manejo de los datos y fácil mantenimiento en caso de requerir modificaciones.

Al tratarse de un ejemplo muy básico, con tiempo limitado, he dejado el proceso de importación como un proceso secuencial,
en blucle, ejecutado desde un archivo index.php.

He preferido prestar atención a que los datos quedasen almacenados en memoria
de una forma coherente, respetando buenas prácticas y que sea flexible frente a cambios. 

Para ello, he creado un  patrón Singleton que asegura que cada importación almacene los datos en una única instancia de torneo.
He llamado torneo al conjunto de juegos que son procesados en cada importación.