import { Component, ChangeDetectionStrategy, ViewEncapsulation, Input } from '@angular/core';

@Component({
  selector: 'mp-layout-main',
  templateUrl: './mp-layout-main.component.html',
  styleUrls: ['./mp-layout-main.component.less'],
  changeDetection: ChangeDetectionStrategy.OnPush,
  encapsulation: ViewEncapsulation.None,
})
export class MpLayoutMainComponent {
  @Input() navigationConfig = '';

  isCollapsed = false;

  updateCollapseHandler(isCollapsed: boolean): void {
    this.isCollapsed = isCollapsed;
  }
}
