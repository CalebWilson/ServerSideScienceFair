use ScienceFair;

drop table if exists Judge;

show warnings;

-- Judges to judge projects
-- referenced by Schedule
create table Judge (
	JudgeID int not null auto_increment,

	FirstName  varchar(50)  not null,
	MiddleName varchar(50),
	LastName   varchar(50)  not null,
	Title      varchar(50), -- e.g. Dr, Professor, etc.
	DegreeID   int          not null, -- highest degree earned
	Employer   varchar(50),

	Email      varchar(50) not null,
	Username   varchar(50) not null,
	Password   varchar(50) not null,
	Year       year        not null, -- what year this person judged. If the same human judges on two different years, two different judge records are created.

	-- first, second, and third category preferences
	CatPref1 int,
	CatPref2 int,
	CatPref3 int,

	LowerGradePref int not null, -- lowest grade level the judge would prefer to judge
	UpperGradePref int not null, -- highest grade level the judge would prefer to judge

	-- make sure no category preferences are identical within one judge record
	check (CatPref2 != CatPref1),
	check (CatPref3 != CatPref1 AND CatPref3 != CatPref2),

	unique (   Email, Year), -- no two judges should have the same email the same year
	unique (Username, Year), -- no two judges should have the same username the same year

	primary key (JudgeID),

	foreign key (DegreeID) references Degree     (DegreeID),

	foreign key (CatPref1) references Category (CategoryID),
	foreign key (CatPref2) references Category (CategoryID),
	foreign key (CatPref3) references Category (CategoryID),

	foreign key (UpperGradePref) references Grade (GradeID),
	foreign key (LowerGradePref) references Grade (GradeID)
);

show warnings;

-- default values
drop trigger if exists JudgeInsert;

delimiter //

create trigger JudgeInsert before insert on Judge
for each row
begin

	-- Year
	if new.Year is null
	then
		set new.Year = year(curdate());
	end if;

	-- Degree
	if new.DegreeID is null
	then
		set new.DegreeID =
		(
			select DegreeID
			from Degree
			where DegreeLevel = (select min(DegreeLevel) from Degree)
		);
	end if;

	-- UpperGradePref
	if new.UpperGradePref is null
	then
		set new.UpperGradePref =
		(
			select GradeID
			from Grade
			where GradeNum= (select max(GradeNum) from Grade)
		);
	end if;

	-- LowerGradePref
	if new.LowerGradePref is null
	then
		set new.LowerGradePref =
		(
			select GradeID
			from Grade
			where GradeNum = (select min(GradeNum) from Grade)
		);
	end if;

	-- CatPrefs
	if new.CatPref1 is null and new.CatPref2 is not null
	then
		set new.CatPref1 = new.CatPref2;
		set new.CatPref2 = null;
	end if;

	if new.CatPref2 is null and new.CatPref3 is not null
	then
		set new.CatPref2 = new.CatPref3;
		set new.CatPref3 = null;
	end if;

	if new.CatPref1 is null and new.CatPref2 is not null
	then
		set new.CatPref1 = new.CatPref2;
		set new.CatPref2 = null;
	end if;

end;
//
delimiter ;

drop trigger if exists JudgeUpdate;

delimiter //

create trigger JudgeUpdate before update on Judge
for each row
begin

	-- Year
	if new.Year is null
	then
		set new.Year = year(curdate());
	end if;

	-- Degree
	if new.DegreeID is null
	then
		set new.DegreeID =
		(
			select DegreeID
			from Degree
			where DegreeLevel = (select min(DegreeLevel) from Degree)
		);
	end if;

	-- UpperGradePref
	if new.UpperGradePref is null
	then
		set new.UpperGradePref =
		(
			select GradeID
			from Grade
			where GradeNum= (select max(GradeNum) from Grade)
		);
	end if;

	-- LowerGradePref
	if new.LowerGradePref is null
	then
		set new.LowerGradePref =
		(
			select GradeID
			from Grade
			where GradeNum = (select min(GradeNum) from Grade)
		);
	end if;

	-- CatPrefs
	if new.CatPref1 is null and new.CatPref2 is not null
	then
		set new.CatPref1 = new.CatPref2;
		set new.CatPref2 = null;
	end if;

	if new.CatPref2 is null and new.CatPref3 is not null
	then
		set new.CatPref2 = new.CatPref3;
		set new.CatPref3 = null;
	end if;

	if new.CatPref1 is null and new.CatPref2 is not null
	then
		set new.CatPref1 = new.CatPref2;
		set new.CatPref2 = null;
	end if;

end;
//
delimiter ;

-- test
insert into
	Judge  (FirstName, MiddleName, LastName,    Title,    DegreeID,   Employer,        Email, Username, Password, year, CatPref1, LowerGradePref, UpperGradePref)
	values ( "Tfirst",  "Tmiddle",  "Tlast", "Ttitle",           4, "Test Emp", "test@t.com",   "user",   "pass", 2020,        2,              9,             12),
	       ( "judge1",   "judge1", "judge1", "judge1",           4,   "judge1", "judge1@j.j", "judge1", "judge1", 2020,        3,              9,             12),
	       ( "judge2",   "judge2", "judge2", "judge2",           4,   "judge2", "judge2@j.j", "judge2", "judge2", 2020,        4,              9,             12)
;

select * from Judge;
