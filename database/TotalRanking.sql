/*
	TotalRanking.sql

	TotalRanking is a view designed to represent the general goodness of a Project
	based on how it ranks among the other Projects scored by the same Judge.
	The higher the number, the better the Project.

	This metric is "mean-adjusted," meaning that for each Judge, the ranks of their
	Projects have been centered around 0 (for instance, if a Judge scores three
	Projects, their ranks will be -1, 0, and 1. If a Judge scores two Projects,
	their ranks will be -0.5 and 0.5). This ensures that, for example, a low rank
	from a Judge who has scored more Projects will have a bigger impact than a
	low rank from a Judge who has scored fewer Projects.
*/

create or replace view TotalRanking
as
	select
		Project.ProjectID,
		Project.ProjectNum,
		Project.Title,

		-- sum of all mean-adjusted rankings
		sum(adjustedRankings.adjustedRanking) as TotalRank

	from
		Project,
		(

			with
				-- unadjusted rankings
				rawRankings as
				(
					select
						JudgingID, ProjectID, JudgeID, Score,
						(row_number() over(partition by JudgeID order by Score asc)) - 1 as Ranking
					from Judging
				)

			select 
				individual.ProjectID,
				individual.JudgeID,
				individual.Score,

				-- mean-adjusted ranking for each judge
				individual.Ranking - aggregate.mid as adjustedRanking

			from
				rawRankings as individual,
				(
					select
						JudgeID,
						avg (Ranking) as mid -- midpoint of all the project rankings for each judge
					from rawRankings
					group by JudgeID

				) as aggregate

			where individual.JudgeID = aggregate.JudgeID

		) as adjustedRankings
	
	-- join on ProjectID
	where adjustedRankings.ProjectID = Project.ProjectID

	group by Project.ProjectID
	order by TotalRank desc
;
