use ScienceFair;

drop table if exists Schedule;
drop table if exists Judging;

show warnings;

-- each record in this table represents a specific judging of a specific project
create table Judging
(
	JudgingID  int NOT NULL AUTO_INCREMENT,

	JudgeID    int NOT NULL, -- the judge to judge the project
	ProjectID  int NOT NULL, -- the project to be judged

	-- Score out of 100
	Score decimal (5, 2),
		CHECK (Score >= 0 AND Score <= 100.00),

	UNIQUE (JudgeID, ProjectID),   -- a judge cannot judge the same project more than once

	PRIMARY KEY (JudgingID),

	FOREIGN KEY (ProjectID) REFERENCES Project (ProjectID),
	FOREIGN KEY (  JudgeID) REFERENCES Judge   (  JudgeID)
);

show warnings;

-- test
insert into
	Judging (JudgeID, ProjectID, Score)
	values  (      1,         1,   100),
	        (      1,         2,    90),
			  (      2,         1,    75),
			  (      2,         2,    50),
			  (      3,         1,    70),
			  (      3,         2,    80)
;

select * from Judging;
