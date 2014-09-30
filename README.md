KifDoctrineToTypescriptBundle
=============================



This is a command line tool for converting doctrine entities into Typescript (.ts) language files

<code>kif:doctrine:typescript:generate</code>

##What does this bundle do?


* convert doctrine entities to typescript classes.
* recognize type of the doctrine entities, based upon the file ending of the files in the directory (yml,php are supported sofar)


##Todo
* set a --destination parameter which changes the default destination folder
* set a --exposed-only, which would make sure jms serializer is installed, and generate only exposed entities or/and variables.


