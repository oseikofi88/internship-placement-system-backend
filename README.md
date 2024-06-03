# INTERNSHIP-PLACEMENT-SYSTEM-BACKEND


This is the backend for the internship placement system developed for my college in 2017


The slim PHP framework was used in conjuction with Laravel's eloquent ORM and Rob Morgan's Phinx for database migration.
(Laravel was too bloated for me for a simple project like this hence my decision to use Slim framework with the laravel ORM) 
The placement algorithm works by either using the Haversine formula to find the distance between two cordintes(longitude & latitiude) 
Or by using Google Maps Distance Matrix API  to find it.
It then uses other factors such as department of student, number of interns needed by companies and department they are to come from, 
etc to put students in companies to intern with.
It was a fun project although old and has not been maintanied in over 7 years as the college no longer uses it.
