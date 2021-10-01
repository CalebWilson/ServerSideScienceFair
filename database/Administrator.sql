use ScienceFair;

drop table if exists Administrator;

show warnings;

create table Administrator (
	AdministratorID int         NOT NULL AUTO_INCREMENT,
	FirstName       varchar(50) NOT NULL,
	MiddleName      varchar(50),
	LastName        varchar(50) NOT NULL,
	Email           varchar(50) NOT NULL,
	Username        varchar(50) NOT NULL,
	Password        varchar(50) NOT NULL,
	AuthorityLevel  int         NOT NULL, #not sure how this will be used

	UNIQUE (Email),
	UNIQUE (Username),

	PRIMARY KEY (AdministratorID)
);

show warnings;

-- test
insert into
	Administrator (FirstName, MiddleName, LastName,        Email, Username, Password, AuthorityLevel)
	values        ( "Tfirst",    "Tlast",  "Tlast", "test@t.com",  "Tuser",  "Tpass",              1),
	              (  "admin",    "admin",  "admin",      "admin",  "admin",  "admin",              1)
;

select * from Administrator;
