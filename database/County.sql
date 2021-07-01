use casawils_db;

drop table if exists County;

show warnings;

#counties in which schools are located
#referenced by School
create table County (
	CountyID           int NOT NULL AUTO_INCREMENT,
	CountyName varchar(50) NOT NULL,

	UNIQUE (CountyName),

	PRIMARY KEY (CountyID)
);

show warnings;

#test
insert into
	County (CountyName)
	values
	       ("Test County 1"),
	       ("Test County 2"),
	       ("Test County 3"),
	       ("Test County 4"),
	       ("Test County 5")
;

select * from County;

-- deletion
drop trigger if exists CountyDelete;

delimiter //

create trigger CountyDelete before delete on County
for each row
begin

	declare dependents int;

	select count(*) into  dependents
	from    School  where School.CountyID = old.CountyID;

	if dependents > 0
	then
		signal sqlstate '45000'
		set message_text = old.CountyName;
	end if;
end;
//
delimiter ;
