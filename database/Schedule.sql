use ScienceFair;

drop table if exists Schedule;

show warnings;

-- each record in this table represents a specific judging of a specific project
create table Schedule (
	ScheduleID int NOT NULL AUTO_INCREMENT,
	SessionID  int NOT NULL, -- the time slot during which the project is to be judged
	ProjectID  int NOT NULL, -- the project to be judged
	JudgeID    int NOT NULL, -- the judge to judge the project

	-- Score out of 100
	Score decimal (5, 2),
		CHECK (Score >= 0), --  AND Score <= 100.00),
	
	Ranking int,

	UNIQUE (SessionID, JudgeID),   -- a judge cannot judge multiple projects at the same time
	UNIQUE (ProjectID, JudgeID),   -- a judge cannot judge the same project more than once

	PRIMARY KEY (ScheduleID),

	FOREIGN KEY (SessionID) REFERENCES Session (SessionID) on delete cascade,
	FOREIGN KEY (ProjectID) REFERENCES Project (ProjectID),
	FOREIGN KEY (  JudgeID) REFERENCES Judge   (  JudgeID)
);

show warnings;

-- test
insert into
	Schedule (SessionID, ProjectID, JudgeID)
	values   (        1,         1,       1);

select * from Schedule;
