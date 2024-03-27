import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
    name: 'localTime',
})
export class LocalTimePipe implements PipeTransform {
    transform(utcTimeString: string): string {
        const utcDate = new Date(utcTimeString);
        const timezoneOffsetMilliseconds = new Date().getTimezoneOffset() * 60000;
        const localTime = new Date(utcDate.getTime() - timezoneOffsetMilliseconds);

        return this.formatTime(localTime);
    }

    private formatTime(date: Date): string {
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');

        return `${hours}:${minutes}:${seconds}`;
    }
}
