/*

	Zoocha compiling/theming guide

*/


This theme is built on Bootstrap 3.0 and is therefore using LESS. A grunt file has been added to the repo and can be run by simply navigating to the the root of the project theme folder and running 'grunt'. This will work, assuming the correct packages are installed (you need to install node.js and then run npm install in the theme project folder) - this will set up the project automatically (if the package.json is present and configured correctly). When complete, type 'grunt' on the command line and the compiler launches and is set up to watch the less folder. More info available here: 

http://ericnishio.com/blog/compile-less-files-with-grunt

/* ----------------------------------------------------------------- */

ALETERNATIVE COMPILER METHOD 1: USING RECESS TO COMPILE:

The base LESS file is: sites/all/themes/<zoocha-project>/less/style.less and it compiles to the following file: sites/all/themes/<zoocha-project>/css/style.css

More info here: http://twitter.github.io/recess/    and/or    https://github.com/twitter/recess

Once all correct packages are installed (node and recess), use the following commands:

- To compile:

recess sites/all/themes/<zoocha-project>/less/style.less --compile > sites/all/themes/<zoocha-project>/css/style.css

- To compress and compile:

recess sites/all/themes/<zoocha-project>/less/style.less --compress --compile > sites/all/themes/<zoocha-project>/css/style.css

- To set up a watch folder to auto-compile:

recess sites/all/themes/<zoocha-project>/less/style.less:css/style.css --watch less/

/* ----------------------------------------------------------------- */

ALETERNATIVE COMPILER METHOD 2: USING LESSC to compile:

See http://lesscss.org/