import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MpHeaderComponent } from './mp-header.component';

describe('MpHeaderComponent', () => {
  let component: MpHeaderComponent;
  let fixture: ComponentFixture<MpHeaderComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MpHeaderComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MpHeaderComponent);
    component = fixture.componentInstance;
  });

  it('should create component', () => {
    expect(component).toBeTruthy();
  });
});
