import { Component, OnInit, ViewEncapsulation, ChangeDetectionStrategy, Inject } from '@angular/core';
import { ICONS_TOKEN } from '@spryker/icon';

@Component({
  selector: 'mp-mp-profile',
  templateUrl: './mp-profile.component.html',
  styleUrls: ['./mp-profile.component.less'],
  changeDetection: ChangeDetectionStrategy.OnPush,
  encapsulation: ViewEncapsulation.None
})
export class MpProfileComponent {
  constructor(@Inject(ICONS_TOKEN) icons) {
    console.log(icons);
  }
}
