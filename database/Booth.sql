use ScienceFair;

drop table if exists Booth;

show warnings;

-- booths that a project can be displayed at
-- referenced by Project
create table Booth (
	BoothID     int  not null auto_increment,
	BoothNum    int  not null, -- user-friendly Booth number

	unique (BoothNum),

	primary key (BoothID)
);

show warnings;

-- test
insert into
	Booth  (BoothNum)
	values (       1),
	       (       2)
;

select * from Booth;
