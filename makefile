make:
	coffee -c js/chat.coffee

min: make
	uglifyjs js/chat.js -o js/chat.min.js -c drop_console=true -d DEBUG=false
