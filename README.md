# Are they good...
http://aretheygood.timdayley.com

by Tim Dayley (Spacetoaster)

Are they good... is a web application that displays champion mastery information for a specific summoner and region.


### Location

Are they good... can be found at http://aretheygood.timdayley.com


### Current live version

1.0


### Stuff used for development

* Bootstrap - http://getbootstrap.com/
* jQuery - https://jquery.com/
* CSS Percentage Circle - http://circle.firchow.net/
* Riot Games API - https://developer.riotgames.com/
* WampServer - http://www.wampserver.com/en/
* Eclipse - http://www.eclipse.org/downloads/packages/eclipse-php-developers/mars2
* GitHub - https://github.com/


### Overview

Are they good... is a PHP web application that draws its power from the Riot Games API. The code base 
was developed from scratch piecing bits of code that I wrote myself and picked from numerous 
stack overflows. This was my first web application using PHP and an API, so if you have any suggestions 
or comments, please send me an email at development@timdayley.com.

This application came to be from my personal desire to quickly and efficiently view a summoners stats for 
a particular champion. When I start a game of League of Legends, I normally look up the rank and previously 
played games using one of the many websites already available. While this information is useful, the champion 
mastery information gives you a direct link to the summoners skill and the champion in question. This way 
you can adjust your game plan as you see fit (maybe start Long Sword instead of Doran's?) depending on the summoner's 
champion level.

The unique aspect of this web application, is that it stores the data used in a local database so that it 
doesn't need to spam the API with calls. There is also an option to refresh the data for a summoner, if you 
believe it to be outdated. Storing the champion mastery data, gives the site the power to call upon greater 
statistical analysis the more it gets used. As more and more summoners are searched and the information stored, 
the application can grow to be a giant data mine with the ability to query on multiple stats such as 
the average score for a champion, the champion with the most points (arguably most used), and competitive  
analysis like the summoner with the most level 5 champions.

Hopefully you enjoy using this application, and keep checking for future updates where improvements are 
sure to come!


### Can I use your code?

While I urge you to write your own code to learn and improve as a developer, feel free to use anything from this 
project.

If you want to load this project directly into your own environment there are a few things you will have to add.
* A file named private_variables.php placed in the variables folder. This should include the following variables:
	1. '$apiKey = "asdfasdf-1234-1234-1234-asdfasdf' <- this should match the api key obtained from Riot
	2. '$serverName = "localhost";' <- this should match your server name
	3. '$databaseName = "aretheygood";' <- this is your database, named however you see fit
	4. '$username = "root";' <- the username you use to login to your database engine
	5. '$password = "secretpassword";' <- the password you use to login to your database engine
* Create a database to store the information in and make sure it matches the name you gave to the '$databaseName' variable.
* Execute the 2 '.sql' files contained in the root of this project.
	1. 'aretheygood_tables.sql' <- creates the tables needed to store the data obtained from the API calls
	2. 'region_information.sql' <- execute this second, as it populates the region table with data

### Licensing

Please see the file called LICENSE.