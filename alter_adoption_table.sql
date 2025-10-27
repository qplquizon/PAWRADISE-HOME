-- SQL commands to alter the adoption_requests table to add new columns for the additional form fields

ALTER TABLE adoption_requests ADD COLUMN occupation VARCHAR(255);
ALTER TABLE adoption_requests ADD COLUMN pets_allowed ENUM('yes', 'no');
ALTER TABLE adoption_requests ADD COLUMN family_support ENUM('yes', 'no');
ALTER TABLE adoption_requests ADD COLUMN home_visit_permission ENUM('yes', 'no');
ALTER TABLE adoption_requests ADD COLUMN other_pets_type ENUM('cat', 'dog');
ALTER TABLE adoption_requests ADD COLUMN other_pets_count TEXT;
ALTER TABLE adoption_requests ADD COLUMN spayed_neutered ENUM('yes', 'no', 'other');
ALTER TABLE adoption_requests ADD COLUMN spayed_neutered_details TEXT;
ALTER TABLE adoption_requests ADD COLUMN move_with_pets ENUM('yes', 'no');
ALTER TABLE adoption_requests ADD COLUMN monetary_support ENUM('yes', 'no');
ALTER TABLE adoption_requests ADD COLUMN secure_home ENUM('yes', 'no');
ALTER TABLE adoption_requests ADD COLUMN responsibility_commitment ENUM('yes', 'no');
