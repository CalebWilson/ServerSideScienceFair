select
	ProjectID, avg(Ranking) as AvgRanking
	from
	(
		select
			ProjectID, JudgeID, Score,
			ROW_NUMBER() OVER(partition by JudgeID order by Score desc) as Ranking
		from Judging

	) as Rankings

	group by ProjectID
;


-- select ProjectID, JudgeID, avg(Score)
