use ScienceFair;

drop table if exists Booth;

show warnings;

#booths that a project can be displayed at
#referenced by Project
create table Booth (
	BoothID     int  NOT NULL AUTO_INCREMENT,
	BoothNum    int  NOT NULL, #user-friendly Booth number
	Active      bool NOT NULL,

	UNIQUE (BoothNum),

	PRIMARY KEY (BoothID)
);

show warnings;

#test
insert into
	Booth  (BoothNum, Active)
	values (          1,  false)
;

select * from Booth;
