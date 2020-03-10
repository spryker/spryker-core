import {ChangeDetectionStrategy, Component, ViewEncapsulation} from '@angular/core';

@Component({
  selector: 'mp-dashboard',
  templateUrl: './mp-dashboard.component.html',
  styleUrls: ['./mp-dashboard.component.less'],
  changeDetection: ChangeDetectionStrategy.OnPush,
  encapsulation: ViewEncapsulation.None,
})
export class MpDashboardComponent {
}
