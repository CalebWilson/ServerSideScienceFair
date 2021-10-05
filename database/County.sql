use ScienceFair;

drop table if exists County;

show warnings;

-- counties in which schools are located
-- referenced by School
create table County (
	CountyID           int not null auto_increment,
	CountyName varchar(50) not null,

	unique (CountyName),

	primary key (CountyID)
);

show warnings;

-- test
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
