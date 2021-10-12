SERVER-SIDE SCIENCE FAIR

INTRODUCTION:

	This is a website that could hypothetically be used by the administrators and
	judges of a science fair. Administrators can add, read, update, and delete all
	the following entities:

		- Administrators* of the science fair
		- Judges, who score Projects
		- Students, who have created Projects
		- Grade levels that Students belong to
		- Schools that Students attend
		- Counties where Schools are located
		- Projects that Students have created
		- Booths where Projects are to be presented
		- Categories that Projects can pertain to

	However, administrators must have extra authority to manange other
	administrators or update their own authority.

	Administrators can also view Project Rankings, which is a metric of how all
	Judges who scored each Project feel about it in comparison with all the other
	Projects those Judges scored. See database/TotalRanking.sql for details.

	Judges can add, read, update, and delete scores for any Project.


DATABASE DESIGN:

	This website uses a relational database with the following related tables:

                                     Degree
                                        |
                                        ^
                                      Judge
                                        |
                                        ^
                                     Judging
                                        v
                                        |
                       Booth -|---|- Project >---- Category
                                        v
                                        |
                         Grade ----< Student
                                        v
                                        |
                                      School
                                        v
                                        |
                                      County

	The only table not included in this network of foreign key relationships is
	Administrator.


CLASS DESIGN:

	The main design of this system revolves around the Entity class hierarchy:

                                   ReadOnlyEntity
                                         /|
                                        / |
                              Ranking---  |
                                          |
                                       Entity
                                         /\
                                        /  \
                                   -----    -----
                                  /              \
                                 /                \
                          PasswordEntity           \
                               /\                   \
                              /  \                   \
                           ---    ---                 \
                          /          \                 \
                         /            \                 \
                   Administrator     Judge   [Other Concrete Entities]

	Every concrete class in this hierarchy represents an entity in the database
	that has data to be displayed. Descendents of Entity have buttons and forms for
	adding, updating, and deleting records. PasswordEntity adds some functionality
	that is common between Entities that require a password to be entered.

	There are two additional classes that Entities can make use of: Input, and
	AutofillNumber. Input is responsible for returning HTML that will render inputs
	on the screen, such as text inputs and dropdown menus. Input also has methods
	for data validation, including checking whether the given values are unique,
	or blank.

	AutofillNumber's purpose is more niche, only used by Entities that need to
	generate a user-facing number to accompany a record in a table. Such Entities
	can declare an instance of AutofillNumber as an attribute, and initialize it
	in the constructor. It is also necessary to override Entity::edit() to set the
	AutofillNumber's ID before calling parent::edit(). Finally, autofilling
	Entities override Entity::insert() and Entity::update() to call
	autofill_number() on their instance of AutofillNumber.


RUNNING THE WEBSITE LOCALLY:

	First, navigate to the database/ directory:
		- cd database/

	Then, start the MySQL database service with:
		- sudo service mysql start
	
	Next, to set up the database, start a MySQL shell with:
		- sudo mysql -h localhost -p

	and enter your password. You may have to create an account if you don't have
	one already.

	Finally, once inside the MySQL shell, to create the database and populate it
	with basic test data, do:
		- source database.sql
	
	Now is probably a good time to get credentials from the database if you want to
	log in and experiment with the system and user interface. To get Administrator
	usernames and passwords, do:
		- select Username, Password from Administrator
	
	To get Judge usernames and passwords, do:
		- select Username, Password from Judge
	
	I know it's normally a terrible idea to store passwords in plaintext, but this
	is just a proof-of-concept and I wanted it to be easy for you to log in.

	Now that the database is set up, exit the MySQL shell and navigate back to the
	main directory:
		- exit
		- cd ..

	To run the PHP server locally, do:
		- php -S 127.0.0.1:8000
	
	Navigate to 127.0.0.1:8000 in your browser, and you will be redirected to the
	administrator login. From there, you can either log in as an administrator
	or click "Judge Login" from the menu to see the judge login. In either case,
	you can then log in using the credentials obtained from the appropriate
	`select` statements above.


CONCLUSION:
	This started out as the final project in a class called "Server-Side
	Programming for the Web," which I took the first semester of my senior year as
	a Computer Science Student at IUPUI. At the beginning of this class, I had no
	experience whatsoever with PHP, so most of the code was very basic. At the end
	of the semester, I had a mainly functional website, but the code was... of
	questionable quality, to be generous.

	In the months since my graduation, I have taken the time to polish the project,
	abstracting away much boilerplate code to the point where I almost have the
	beginnings of a PHP framework. This has been a very enlightening and enriching
	experience, and I hope the final product will serve as a testimony to my
	abilities as a software developer.
