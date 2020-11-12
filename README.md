<h3>Primero que nada, este proyecto utilizó los distintos plug ins de PHP Storm:</h3>

-PHP Toolbox <br>
-PHP Annotations <br>
-Symfony Support <br>
-.env files support <br>
-Handlebars/Mustache <br>
-Ideolog <br>

<h3>Y utiliza los distintos lenguajes y programas:</h3>

12-11-2020 <br>
-PHP <br>
-Symfony <br>
-JavaScript <br>
-mySQL <br>
-JQuery <br>
-Bootstrap <br>
-node <br>
-yarn <br>
-Twig <br>
-Git <br>

<h3>Recordar activar Symfony en el proyecto en las propiedades del proyecto: </h3><br>
Settings/Languague & Frameworks/Symfony ---- Enable plug in for this project <br>

Recordar cambiar la configuración web del proyecto a public en caso de que no esté configurada ya
Settings/Languague & Frameworks/PHP->Symfony ---- web directory "public" <br>

Comandos que debes utilizar en orden para utilizar este proyecto: <br>

composer require annotations (Rutas Inteligentes) <br>
composer require sec-checker (Seguridad) <br>
composer require template (Twig)                     (https://twig.symfony.com/ para leer documentación) <br>
composer require profiler --dev (Herramienta profiler) <br>
composer require debug (Herramienta para debug complementaria a profiler) <br>
composer require symfony/asset (prefix automatico para rutas) <br>
composer require encore (Desarrollo Front End) <br>

<h3>LUEGO DE INSTALAR EL ENCORE, EJECUTAR EL SIGUIENTE COMANDO PARA LA INSTALACION DE WEBPACK ENCORE: </h3><br>
yarn install <br>

yarn add jquery --dev (Añadir JQuery al proyecto con node) <br>
yarn add bootstrap --dev (Añadir Bootstrap al proyecto con node) <br>

composer require maker --dev (Herramienta util a la hora de crear objetos de manera rápida y sencilla) <br>
composer require orm (Doctrine, BD) <br>
composer require knplabs/knp-markdown-bundle (Markdown filter para formatear la información) <br>
composer require stof/doctrine-extensions-bundle (Extensiones para doctrine, urlizar las imagenes)  <br>
composer require security (login) <br>
composer require --dev orm-fixtures (Rellenar la BD con datos falsos de práctica, me permitió ingresar contraseñas
codificadas) <br>

Esas son todas las herramientas que utilicé para realizar este proyecto, ¡fue entretenido!
