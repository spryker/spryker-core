import { Component, ViewEncapsulation, ChangeDetectionStrategy } from '@angular/core';

@Component({
	selector: 'mp-profile',
	templateUrl: './profile.component.html',
	styleUrls: ['./profile.component.less'],
	changeDetection: ChangeDetectionStrategy.OnPush,
	encapsulation: ViewEncapsulation.None,
	host: {'class': 'mp-profile'},
})
export class ProfileComponent {}
