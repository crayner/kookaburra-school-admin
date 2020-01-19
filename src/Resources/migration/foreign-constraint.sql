ALTER TABLE `__prefix__AcademicYearSpecialDay`
    ADD CONSTRAINT FOREIGN KEY (`academic_year`) REFERENCES `__prefix__AcademicYear` (`id`);

ALTER TABLE `__prefix__AcademicYearTerm`
    ADD CONSTRAINT FOREIGN KEY (`academic_year`) REFERENCES `__prefix__AcademicYear` (`id`);

ALTER TABLE `__prefix__INPersonDescriptor`
    ADD CONSTRAINT FOREIGN KEY (`alert_level`) REFERENCES `__prefix__AlertLevel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
    ADD CONSTRAINT FOREIGN KEY (`in_descriptor`) REFERENCES `__prefix__INDescriptor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
    ADD CONSTRAINT FOREIGN KEY (`person`) REFERENCES `__prefix__Person` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `__prefix__ScaleGrade`
    ADD CONSTRAINT FOREIGN KEY (`scale`) REFERENCES `__prefix__Scale` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `__prefix__YearGroup`
    ADD CONSTRAINT FOREIGN KEY (`head_of_year`) REFERENCES `__prefix__Person` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE __prefix__ScaleGrade ADD CONSTRAINT FOREIGN KEY (scale) REFERENCES __prefix__Scale (id);
ALTER TABLE __prefix__AcademicYearSpecialDay ADD CONSTRAINT FOREIGN KEY (academic_year) REFERENCES __prefix__AcademicYear (id);
ALTER TABLE __prefix__AcademicYearTerm ADD CONSTRAINT FOREIGN KEY (academic_year) REFERENCES __prefix__AcademicYear (id);
ALTER TABLE `__prefix__FacilityPerson` ADD CONSTRAINT FOREIGN KEY (`facility`) REFERENCES `__prefix__Facility` (`id`),
    ADD CONSTRAINT FOREIGN KEY (`person`) REFERENCES `__prefix__Person` (`id`);
