use ScienceFair;

drop table if exists Project;

show warnings;

-- referenced by Student, Schedule
create table Project (
	ProjectID       int         NOT NULL AUTO_INCREMENT,
	BoothID         int,                  -- booth where the project is shown
	CategoryID      int         NOT NULL, -- genre of the project

	ProjectNum      int         NOT NULL, -- user-friendly Project number
	Title           varchar(50) NOT NULL, -- Project Name
	Abstract        varchar(200),         -- Project description
	AverageRanking  int,                  -- average ranking of the Project from the Judges who have judged it

	CourseNetworkID int,          -- no clue what this is but whatever
	Year            year NOT NULL, -- the year the project was presented

	UNIQUE (ProjectNum, year),
	UNIQUE (Title, year),
	UNIQUE (BoothID, year),

	PRIMARY KEY (ProjectID),
	FOREIGN KEY (BoothID)    REFERENCES Booth (BoothID)
		on delete set null,
	FOREIGN KEY (CategoryID) REFERENCES Category (CategoryID)
);

show warnings;

-- test
insert into
	Project (BoothID, CategoryID, ProjectNum,  Title, Abstract, AverageRanking,            Year)
	values  (      1,          2,          1, "Test", "Test",                1, YEAR(CURDATE()))
;

select * from Project;

-- year insertion
drop trigger if exists ProjectYear;

delimiter //

create trigger ProjectYear before insert on Project
for each row
begin

	set new.Year = YEAR(CURDATE());

end;
//
delimiter ;

-- booth modification
drop procedure if exists UpdateProjectBooth;

delimiter //

create procedure UpdateProjectBooth (in oldProjectID int, in newBoothID int)
begin

	declare oldBoothID int;
	
	select max(BoothID)
		into oldBoothID
		from Project
		where ProjectID = oldProjectID;

	update Project
	set BoothID = null
	where ProjectID = oldProjectID;

	update Project
	set BoothID = oldBoothID
	where BoothID = newBoothID;

	update Project
	set BoothID = newBoothID
	where ProjectID = oldProjectID;

end;
//
delimiter ;

-- deletion
drop trigger if exists ProjectDelete;

delimiter //

create trigger ProjectDelete before delete on Project
for each row
begin

	declare dependents int;

	select count(*) into dependents
	from Student where Student.ProjectID = old.ProjectID;

	if dependents > 0
	then
		signal sqlstate '45000'
		set message_text = old.Title;
	end if;

end;
//
delimiter ;
