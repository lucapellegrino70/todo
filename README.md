# todo

To enable SQLite on my Windows/Apache/PHP setup, I uncomment the following lines in the php.ini file and restart Apache: <br/>

extension=php_pdo.dll<br/>
extension=php_pdo_sqlite.dll<br/>

Add host in your apache/nginx server configuration file. <br/>
Example for local installation on Apache server:<br/><br/>

<VirtualHost 127.0.0.1:80><br/>
	<Directory "C:/development/todo-list/"><br/>
		Options FollowSymLinks Indexes<br/>
		AllowOverride All<br/>
		Order deny,allow<br/>
		allow from All<br/>
	</Directory><br/>
	ServerName todo-list.local<br/>
	ServerAlias todo-list.local<br/>
	DocumentRoot "C:/development/todo-list/"<br/>
	ErrorLog "C:/Ampps/apache/logs/todo-list.err"<br/>
	CustomLog "C:/Ampps/apache/logs/todo-list.log" combined<br/>
</VirtualHost><br/><br/>

Add domain in your Windows / Linux:<br/><br/>

Example for Windows (edit file hosts):<br/>
127.0.0.1	todo-list.local<br/><br/>

In the browser navigate to:<br/><br/>

http://todo-list.local or the url you specified in the configuration files


