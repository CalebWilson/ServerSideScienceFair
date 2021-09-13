use ScienceFair;

drop procedure if exists ScoreProject;

delimiter //

create procedure ScoreProject(in newProjectID int, in newJudgeID int, in newScore decimal(4, 2))
begin

	declare better int;
	declare newRanking int;
	declare oldScore decimal(4, 2);

	declare exit handler for 1442 begin end;

	-- get the old score
	select Score
	into oldScore
	from Schedule
	where
		JudgeID = newJudgeID and
		ProjectID = newProjectID;

	if oldScore is null
	-- demote projects with scores worse than new score
	then
		update Schedule
		set Ranking = Ranking + 1
		where Score < newScore and
		JudgeID = newJudgeID;

	else
	-- demote projects with scores worse than new score, but better than old score
		update Schedule
		set Ranking = Ranking + 1
		where
			Score < newScore and
			Score > oldScore and
			JudgeID = newJudgeID;

	-- promote projects with scores better than new score, but worse than old score
		update Schedule
		set Ranking = Ranking - 1
		where
			Score > newScore and
			Score < oldScore and
			JudgeID = newJudgeID;
	end if;

	-- if best project so far, set rank to 1
	select count(*)
	into better
	from Schedule
	where
		Score > newScore and
		JudgeID = newJudgeID;

	if better = 0 then
		set newRanking = 1;

	-- else replace best project below it
	else
		-- get the rank of the worst project above it
		select max(Ranking) + 1
		into newRanking
		from Schedule
		where
			Score > newScore and
			JudgeID = newJudgeID;
	end if;

	-- update the scored project
	update Schedule
	set
		Score = newScore,
		Ranking  = newRanking
	where
		JudgeID   = newJudgeID and
		ProjectID = newProjectID;

end;
//
delimiter ;
