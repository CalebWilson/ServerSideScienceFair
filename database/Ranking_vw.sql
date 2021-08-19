use ScienceFair;

create or replace view Ranking_vw
as
	select
		ProjectNum as RankingID,
		ProjectNum,
		Title,
		AVG(Ranking) as AvgRank
	from
		Schedule,
		Project
	where Schedule.ProjectID = Project.ProjectID
	group by ProjectNum
	order by AvgRank;
