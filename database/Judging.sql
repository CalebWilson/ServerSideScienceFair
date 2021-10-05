use ScienceFair;

drop table if exists Schedule;
drop table if exists Judging;

show warnings;

-- each record in this table represents a specific judging of a specific project
create table Judging
(
	JudgingID  int not null auto_increment,

	JudgeID    int not null, -- the judge to judge the project
	ProjectID  int not null, -- the project to be judged

	-- Score out of 100
	Score decimal (5, 2),
		check (Score >= 0 AND Score <= 100.00),

	unique (JudgeID, ProjectID),   -- a judge cannot judge the same project more than once

	PRIMARY KEY (JudgingID),

	foreign key (ProjectID) references Project (ProjectID) on delete cascade,
	foreign key (  JudgeID) references Judge   (  JudgeID) on delete cascade
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
