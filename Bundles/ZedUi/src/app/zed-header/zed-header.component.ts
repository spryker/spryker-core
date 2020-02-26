import {ChangeDetectionStrategy, Component, ViewEncapsulation} from '@angular/core';

@Component({
  selector: 'zed-header',
  templateUrl: './zed-header.component.html',
  styleUrls: ['./zed-header.component.less'],
  changeDetection: ChangeDetectionStrategy.OnPush,
  encapsulation: ViewEncapsulation.None,
})
export class ZedHeaderComponent {
}
