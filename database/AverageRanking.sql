create or replace view AverageRanking
as
	select ProjectID, avg(Ranking) as AvgRank
	from
	(
		select
			ProjectID, JudgeID, Score,
			ROW_NUMBER() OVER(partition by JudgeID order by Score desc) as Ranking
		from Judging

	) as Rankings

	group by ProjectID
;
