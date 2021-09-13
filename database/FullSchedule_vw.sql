use ScienceFair;

create or replace view FullSchedule_vw
as
	select
		SessionProject.SessionID as SessionID,
		SessionProject.StartTime as StartTime,
		SessionProject.EndTime   as EndTime,
		SessionProject.ProjectID as ProjectID,
		SessionProject.BoothNum  as BoothNum,
		Judge.Title              as Title,
		Judge.FirstName          as FirstName,
		Judge.LastName           as LastName

	from
		Judge                                        inner join
		Schedule on Judge.JudgeID = Schedule.JudgeID right join
		(
			select
				SessionID, StartTime, EndTime,
				ProjectID, BoothNum
			from
				Session, Project, Booth
			where Project.BoothID = Booth.BoothID
			order by StartTime, ProjectID

		) as SessionProject
			on
				SessionProject.SessionID = Schedule.SessionID and
				SessionProject.ProjectID = Schedule.ProjectID
	order by
		SessionProject.StartTime,
		SessionProject.BoothNum
;
