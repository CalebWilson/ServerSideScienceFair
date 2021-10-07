use ScienceFair;

drop table if exists Student;

show warnings;

-- Students participating in the program
-- A new record is added when a Student participates a second year
create table Student (
	StudentID  int          not null auto_increment,
	SchoolID   int          not null, -- school attended by the Student
	ProjectID  int          not null, -- project the Student worked on
	FirstName  varchar(50)  not null,
	MiddleName varchar(50),
	LastName   varchar(50)  not null,
	GradeID    int          not null, -- what grade the Student is in
	Gender     varchar(50)  not null,
	Year       year default 0,        -- which year this Student is participating

	primary key (StudentID),
	foreign key (SchoolID)  references School   (SchoolID),
	foreign key (ProjectID) references Project  (ProjectID),
	foreign key (GradeID)   references Grade    (GradeID)
);

show warnings;

-- year insertion
drop trigger if exists StudentYear;

delimiter //

create trigger StudentYear before insert on Student
for each row
begin

	if new.Year = 0 then
		set new.Year = YEAR(CURDATE());
	end if;

end;
//
delimiter ;


-- test
insert into
	Student(SchoolID, ProjectID, FirstName, MiddleName, LastName, GradeID,  Gender)
	values (       1,         1,   "Tfirst",  "Tmiddle",  "Tlast",     12, "Gtest")
;

select * from Student;
