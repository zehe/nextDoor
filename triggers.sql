delimiter $$
# this trigger is to remove/update some relative data when a user leaves a blcok 
-- DROP TRIGGER MOVEIN;$$
CREATE trigger MOVEIN after insert on MoveIn
for each row 
begin
delete from WaitingList 
where WUserId = new.MoveUserId;
delete from ApproveList
where AuserId = new.MoveUserId;
delete from Neighbor
where NUserId1 = new.MoveUserId or NUserId2 = new.MoveUserId;
update User
set User.blockid = new.blockId
where User.userId = new.MoveUserId;
end
$$
# this trigger is to delete useless data in friendwaitinglist when insert friend
Drop Trigger FRIEND;$$
create trigger FRIEND BEFORE INSERT ON friend
for each row
begin
delete from FriendWaitingList
where (FWUserId1 = new.FUserId1 and FWUserId2 = new.FUserId2) or (FWUserId1 = new.FUserId2 and FWUserId2 = new.FUserId1);
end
$$
#this trigger is to delete date from waitinglist when a request is approved
-- Drop trigger APPROVELIST$$
-- $$
-- # this trigger is to insert user to the member table once he meets the requirement
-- drop trigger approve$$
Create trigger approve after insert on approveList
for each row
begin
if (select count(RequestId) from AppUserIdroveList where RequestId = new.requestId group by RequestId) > 2
then
set @id= (select WUserId from WaitingList where RequestId = new.RequestId);
set @block = (select BlockId from WaitingList where RequestId = new.RequestId);
insert into Member values(@id,now(),@block);
delete from WaitingList
where RequestId = new.RequestId;
end if;
end
-- 

