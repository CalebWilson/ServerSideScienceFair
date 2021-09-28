use ScienceFair;

drop table if exists Judge;

show warnings;

-- Judges to judge projects
-- referenced by Schedule
create table Judge (
	JudgeID int NOT NULL AUTO_INCREMENT,

	FirstName  varchar(50)  NOT NULL,
	MiddleName varchar(50),
	LastName   varchar(50)  NOT NULL,
	Title      varchar(50), -- e.g. Dr, Professor, etc.
	Degree     int          NOT NULL, -- highest degree earned
	Employer   varchar(50),

	Email      varchar(50) NOT NULL,
	Username   varchar(50) NOT NULL,
	Password   varchar(50) NOT NULL,
	Year       year        NOT NULL, -- what year this person judged. If the same human judges on two different years, two different judge records are created.

	-- first, second, and third category preferences
	CatPref1 int,
	CatPref2 int,
	CatPref3 int,

	LowerGradePref int NOT NULL, -- lowest grade level the judge would prefer to judge
	UpperGradePref int NOT NULL, -- highest grade level the judge would prefer to judge

	-- make sure no category preferences are identical within one judge record
	CHECK (CatPref2 != CatPref1),
	CHECK (CatPref3 != CatPref1 AND CatPref3 != CatPref2),

	UNIQUE (   Email, Year), -- no two judges should have the same email the same year
	UNIQUE (Username, Year), -- no two judges should have the same username the same year

	PRIMARY KEY (JudgeID),

	FOREIGN KEY (  Degree) REFERENCES Degree     (DegreeID),

	FOREIGN KEY (CatPref1) REFERENCES Category (CategoryID),
	FOREIGN KEY (CatPref2) REFERENCES Category (CategoryID),
	FOREIGN KEY (CatPref3) REFERENCES Category (CategoryID),

	FOREIGN KEY (UpperGradePref) REFERENCES Grade (GradeID),
	FOREIGN KEY (LowerGradePref) REFERENCES Grade (GradeID)
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
	if new.Degree is null
	then
		set new.Degree =
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
	if new.Degree is null
	then
		set new.Degree =
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
	Judge  (FirstName, MiddleName, LastName,    Title,    Degree,   Employer,        Email, Username, Password, year, CatPref1, LowerGradePref, UpperGradePref)
	values ( "Tfirst",  "Tmiddle",  "Tlast", "Ttitle",         4, "Test Emp", "test@t.com",   "user",   "pass", 2020,        1,              9,             12),
	       ( "judge1",   "judge1", "judge1", "judge1",         4,   "judge1",     "judge1", "judge1", "judge1", 2020,        1,              9,             12),
	       ( "judge2",   "judge2", "judge2", "judge2",         4,   "judge2",     "judge2", "judge2", "judge2", 2020,        1,              9,             12)
;

select * from Judge;
