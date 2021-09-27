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
				ROW_NUMBER() OVER(partition by JudgeID order by Score desc) as Ranking
			from Judging

		) as rankings
	
	where rankings.ProjectID = Project.ProjectID

	group by Project.ProjectID
;
