import { Component, ChangeDetectionStrategy, ViewEncapsulation, Input } from '@angular/core';

@Component({
  selector: 'zed-layout-main',
  templateUrl: './zed-layout-main.component.html',
  styleUrls: ['./zed-layout-main.component.less'],
  changeDetection: ChangeDetectionStrategy.OnPush,
  encapsulation: ViewEncapsulation.None,
})
export class ZedLayoutMainComponent {
  @Input() navigationConfig = '';

  isCollapsed = false;

  updateCollapseHandler(isCollapsed: boolean): void {
    this.isCollapsed = isCollapsed;
  }
}
