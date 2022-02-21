# todo
Add host in your apache/nginx server configuration file. 
Example for local installation on Apache server:

<VirtualHost 127.0.0.1:80>
	<Directory "C:/development/todo-list/">
		Options FollowSymLinks Indexes
		AllowOverride All
		Order deny,allow
		allow from All
	</Directory>
	ServerName todo-list.local
	ServerAlias todo-list.local
	DocumentRoot "C:/development/todo-list/"
	ErrorLog "C:/Ampps/apache/logs/todo-list.err"
	CustomLog "C:/Ampps/apache/logs/todo-list.log" combined
</VirtualHost>

Add domain in your Windows / Linux:

Example for Windows (edit file hosts):
127.0.0.1	todo-list.local

In the browser navigate to:

http://todo-list.local or the url you specified in the configuration files


