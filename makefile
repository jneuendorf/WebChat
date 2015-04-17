make:
	coffee -c js/chat.coffee

production: make
	uglifyjs js/chat.js -o js/chat.min.js -c drop_console=true -d DEBUG=false
