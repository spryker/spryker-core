import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ZedLayoutCentralComponent } from './zed-layout-central.component';

describe('ZedLayoutCentralComponent', () => {
  let component: ZedLayoutCentralComponent;
  let fixture: ComponentFixture<ZedLayoutCentralComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ZedLayoutCentralComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ZedLayoutCentralComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
