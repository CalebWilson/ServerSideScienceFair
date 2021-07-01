use casawils_db;

drop table if exists Administrator;

show warnings;

#honestly feel a little shaky about this one lol
create table Administrator (
	AdministratorID int         NOT NULL AUTO_INCREMENT,
	FirstName       varchar(50) NOT NULL,
	MiddleName      varchar(50),
	LastName        varchar(50) NOT NULL,
	Email           varchar(50) NOT NULL,
	Username        varchar(50) NOT NULL,
	Password        varchar(50) NOT NULL,
	AuthorityLevel  int         NOT NULL, #not sure how this will be used
	Active          bool        NOT NULL,

	UNIQUE (Email),
	UNIQUE (Username),

	PRIMARY KEY (AdministratorID)
);

show warnings;

#test
insert into
	Administrator (FirstName, MiddleName, LastName,        Email, Username, Password, AuthorityLevel, Active)
	values        ( "Tfirst",    "Tlast",  "Tlast", "test@t.com",  "Tuser",  "Tpass",              1,  false),
	              (  "admin",    "admin",  "admin",      "admin",  "admin",  "admin",              1,  false)
;

select * from Administrator;
