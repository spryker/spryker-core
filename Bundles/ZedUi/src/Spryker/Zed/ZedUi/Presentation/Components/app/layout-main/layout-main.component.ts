import { Component, ChangeDetectionStrategy, ViewEncapsulation, Input } from '@angular/core';

@Component({
  selector: 'mp-layout-main',
  templateUrl: './layout-main.component.html',
  styleUrls: ['./layout-main.component.less'],
  changeDetection: ChangeDetectionStrategy.OnPush,
  encapsulation: ViewEncapsulation.None,
})
export class LayoutMainComponent {
  @Input() navigationConfig = '';

  isCollapsed = false;

  updateCollapseHandler(isCollapsed: boolean): void {
    this.isCollapsed = isCollapsed;
  }
}
