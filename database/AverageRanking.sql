create or replace view AverageRanking
as
	select
		Project.ProjectID,
		Project.ProjectNum,
		Project.Title,
		avg(rankings.Ranking) as AvgRank

	from
		Project,
		(
			select
				ProjectID, JudgeID, Score,
				row_number() over(partition by JudgeID order by Score desc) as Ranking
			from Judging

		) as rankings
	
	where rankings.ProjectID = Project.ProjectID

	group by Project.ProjectID
;
