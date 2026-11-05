import './bootstrap';

var channel = Echo.private(`schedule.${scheduleId}`);
channel.listen('SectionManagerEvent', (e) => {
    console.log(e.data);
    alert(e.data.id);
});
