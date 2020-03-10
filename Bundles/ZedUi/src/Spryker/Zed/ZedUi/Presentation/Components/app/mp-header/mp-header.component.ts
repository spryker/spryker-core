import {ChangeDetectionStrategy, Component, ViewEncapsulation} from '@angular/core';

@Component({
  selector: 'mp-header',
  templateUrl: './mp-header.component.html',
  styleUrls: ['./mp-header.component.less'],
  changeDetection: ChangeDetectionStrategy.OnPush,
  encapsulation: ViewEncapsulation.None,
})
export class MpHeaderComponent {
}
