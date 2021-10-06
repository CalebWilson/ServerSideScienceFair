use ScienceFair;

drop table if exists Administrator;

show warnings;

create table Administrator (
	AdministratorID int         not null auto_increment,
	FirstName       varchar(50) not null,
	MiddleName      varchar(50),
	LastName        varchar(50) not null,
	Email           varchar(50) not null,
	Username        varchar(50) not null,
	Password        varchar(50) not null,
	AuthorityLevel  int         not null,

	unique (Email),
	unique (Username),

	primary key (AdministratorID)
);

show warnings;

-- test
insert into
	Administrator (FirstName, MiddleName, LastName,        Email, Username, Password, AuthorityLevel)
	values        ( "Tfirst",    "Tlast",  "Tlast", "test@t.com",  "Tuser",  "Tpass",              1),
	              (  "admin",    "admin",  "admin", "admin@a.ad",  "admin",  "admin",              1)
;

select * from Administrator;
