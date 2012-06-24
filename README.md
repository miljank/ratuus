RATUUS is an easy to use, web based system for administration of POSTFIX virtual 
domains and users.

Sounds good, but what does it really mean?

Postfix mail server is able to work with non-system users, users which are not 
known to the operating system. This functionality is very useful since it gives you 
possibility to use one mail server for multiple domains without worrying if usernames 
will overlap. Like this you can have address info@domain1.com and also info@domain2.com 
and they can all point to different mailboxes. These kind of users are usually stored 
in some kind of a database, with MySQL database being most commonly used.

And this is where RATUUS comes into play - it provides user friendly interface 
for managing these virtual users and domains. With RATUUS you can easily create, 
modify and delete domains, users and aliases and all your actions are immediately 
reflected to Postfix configuration.

RATUUS is using the same database layout as famous PostfixAdmin so migration is 
quite simple and supported by RATUUS installer.

INSTALLATION

http://www.ratuus.org/documentation/

AUTHORS

Ratuus is created and maintained by Miljan Karadzic (http://www.miljan.org/).

User interface and web site are kindly designed by eLogodesign (http://www.elogodesign.com). 
Feel free to contact them, they are very open and friendly. :)

LICENSE

Ratuus is released under GNU GPL v3 or any newer version of this license. This means 
you can download Ratuus, use it for any purpose (noncommercial as well as commercial), 
you can modify the code, and, well, do what ever you have in mind with it. For more 
information about the licence please visit Free Software Foundation 
(http://www.fsf.org/licensing/licenses/gpl.html).
