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
	Year       year         not null, -- which year this Student is participating in

	primary key (StudentID),
	foreign key (SchoolID)  references School   (SchoolID),
	foreign key (ProjectID) references Project  (ProjectID),
	foreign key (GradeID)   references Grade    (GradeID)
);

show warnings;

-- test
insert into
	Student (SchoolID, ProjectID, FirstName, MiddleName, LastName, GradeID,  Gender, Year)
	values  (       1,         1,   "Tfirst",  "Tmiddle",  "Tlast",     12, "Gtest", 2020)
;

select * from Student;
