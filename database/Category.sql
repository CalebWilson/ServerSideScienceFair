use ScienceFair;

drop table if exists Category;

show warnings;

-- genre of the project
-- referenced by Project, Judge
create table Category (
	CategoryID   int         not null auto_increment,
	CategoryName varchar(50) not null,

	unique (CategoryName),

	primary key (CategoryID)
);

show warnings;

-- test
insert into
	Category (CategoryName)
	values
				("Other"),
	         ("Test Category"),
				("Agriscience"),
				("Biology"),
				("Chemistry"),
				("Dentistry"),
				("Enviroscience"),
				("Forensics")
;

select * from Category;

-- deletion
drop trigger if exists CategoryDelete;

delimiter //

create trigger CategoryDelete before delete on Category
for each row
begin

	-- variable declarations
	declare other    int; -- the id of the category "Other"

	-- set Project.Category to "Other"
	select CategoryID into  other
	from   Category   where CategoryName = "Other";

	update Project
	set CategoryID = other
	where CategoryID = old.CategoryID;

	-- Judge Category Preferences
	update Judge
	set CatPref1 = null
	where CatPref1 = old.CategoryID;

	update Judge
	set CatPref2 = null
	where CatPref2 = old.CategoryID;

	update Judge
	set CatPref3 = null
	where CatPref3 = old.CategoryID;

end;
// 
delimiter ;
